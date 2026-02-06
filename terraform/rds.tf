# 1) (Optional) See what's currently inside rds.tf
nl -ba rds.tf

# 2) Replace rds.tf with RDS-only content
cat > rds.tf <<'HCL'
# DB Subnet Group in private subnets
resource "aws_db_subnet_group" "db_subnets" {
  name       = "${local.project}-db-subnet-group"
  subnet_ids = [aws_subnet.private_a.id, aws_subnet.private_b.id]

  tags = merge(local.tags, { Name = "${local.project}-db-subnet-group" })
}

# RDS MySQL instance (free-tier class) in private subnets
resource "aws_db_instance" "mysql" {
  identifier                 = "${local.project}-mysql"
  engine                     = "mysql"
  engine_version             = "8.0"
  instance_class             = var.db_instance_class

  allocated_storage          = 20
  storage_type               = "gp2"

  db_name                    = var.db_name
  username                   = var.db_username
  password                   = var.db_password

  db_subnet_group_name       = aws_db_subnet_group.db_subnets.name
  vpc_security_group_ids     = [aws_security_group.rds_sg.id]

  multi_az                   = false
  publicly_accessible        = false
  backup_retention_period    = 1

  deletion_protection        = false
  skip_final_snapshot        = true
  apply_immediately          = true

  tags = merge(local.tags, { Name = "${local.project}-mysql" })
}
HCL
