############################################
# Provider
############################################
terraform {
  required_version = ">= 1.5.0"
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

provider "aws" {
  region = "us-east-1"
}

############################################
# Inputs you can tweak
############################################
variable "ubuntu_ami" {
  type    = string
  default = "ami-0b6c6ebed2801a5cb" # us-east-1 Ubuntu 22.04 (example)
}

variable "key_name" {
  type    = string
  default = "terraform-key"         # <-- MUST exist in us-east-1
}

############################################
# Use default VPC + its default public subnets
############################################
data "aws_vpc" "default" {
  default = true
}

# Default-for-az == AWS-created public subnets of the default VPC
data "aws_subnets" "default_public" {
  filter {
    name   = "vpc-id"
    values = [data.aws_vpc.default.id]
  }
  filter {
    name   = "default-for-az"
    values = ["true"]
  }
}

# use first two public subnets
locals {
  selected_public_subnet_ids = slice(data.aws_subnets.default_public.ids, 0, 2)
}

############################################
# Re-use EXISTING LB, TG, and (optionally) SG
############################################
# Existing ALB by name
data "aws_lb" "app_lb" {
  name = "streamline-alb"           # <-- change if your ALB name differs
}

# Existing Target Group by name
data "aws_lb_target_group" "tg" {
  name = "streamline-tg"            # <-- change if your TG name differs
}

# If you already created an SG named "streamline-web-sg" in this VPC, re-use it:
data "aws_security_group" "web_sg" {
  name   = "streamline-web-sg"      # <-- change if needed OR create a new SG instead (see note below)
  vpc_id = data.aws_vpc.default.id
}

############################################
# (Optional) Simple web server user_data to pass ALB health checks
############################################
locals {
  user_data = <<-EOF
              #!/bin/bash
              apt-get update -y
              apt-get install -y nginx
              echo "Hello from $(hostname)" > /var/www/html/index.html
              systemctl enable nginx
              systemctl start nginx
              EOF
}

############################################
# EC2 instances in public subnets
############################################
resource "aws_instance" "web" {
  count                       = 2
  ami                         = var.ubuntu_ami
  instance_type               = "t3.micro"
  subnet_id                   = local.selected_public_subnet_ids[count.index]
  vpc_security_group_ids      = [data.aws_security_group.web_sg.id]
  associate_public_ip_address = true
  key_name                    = var.key_name
  user_data                   = local.user_data

  tags = {
    Name = "streamline-web-${count.index + 1}"
  }
}

############################################
# Attach instances to EXISTING target group
############################################
resource "aws_lb_target_group_attachment" "attach" {
  count            = length(aws_instance.web)
  target_group_arn = data.aws_lb_target_group.tg.arn
  target_id        = aws_instance.web[count.index].id
  port             = 80
}

############################################
# Outputs
############################################
output "alb_dns_name" {
  value = data.aws_lb.app_lb.dns_name
}

output "web_public_ips" {
  value = aws_instance.web[*].public_ip
}
