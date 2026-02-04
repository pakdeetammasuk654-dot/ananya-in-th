## 2026-02-04 - [N+1 Query in dressColor]
**Learning:** The `dressColor` method was performing a database query for every character in the input string, which resulted in a significant performance bottleneck (N+1 query problem).
**Action:** Replaced the loop with a single `IN` query and used a hash map to reconstruct the response in the correct order, resulting in a ~11x performance improvement. Ensure that verification scripts with sensitive credentials are never committed.
