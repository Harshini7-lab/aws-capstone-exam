variable "region" {
  description = "AWS region to deploy into"
  type        = string
  default     = "ap-south-1" # Mumbai
}

# Your public IP with /32 mask (e.g., 49.207.181.22/32)
variable "allowed_ssh_cidr" {
  description = "CIDR for SSH access to web servers"
  type        = string
}

# Public GitHub repo URL (no auth needed)
variable "git_repo_url" {
  description = "GitHub repository URL containing index.php in the branch to deploy"
  type        = string
}

# Which branch of your repo to deploy (main for v1, dev for v2)
variable "deploy_branch" {
  description = "Branch to deploy (main or dev)"
  type        = string
  default     = "main"
}

# EC2 instance type for web servers
variable "ec2_instance_type" {
  type    = string
  default = "t3.micro"
}

# Database settings
variable "db_instance_class" {
  type    = string
  default = "db.t3.micro"
}

variable "db_name" {
  type    = string
  default = "streamline"
}

variable "db_username" {
  type      = string
  sensitive = true
}

variable "db_password" {
  type      = string
  sensitive = true
}