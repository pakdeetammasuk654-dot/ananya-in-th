package handlers

import "github.com/jackc/pgx/v4/pgxpool"

// DB เป็น global variable สำหรับเก็บ connection pool
var DB *pgxpool.Pool
