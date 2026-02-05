## 2025-01-24 - [N+1 Database Query Optimization]
**Learning:** In legacy PHP codebases, N+1 query patterns are common in loops that process strings or arrays to fetch metadata. These can be optimized using SQL `IN` clauses and hash-map reconstruction to preserve order and duplicates.
**Action:** Always check for database queries inside loops, especially when the loop index is based on request attributes. Use `str_split` + `array_unique` + `PDO::fetchAll` for efficient batch processing.
