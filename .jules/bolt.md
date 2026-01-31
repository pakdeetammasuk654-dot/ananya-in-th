## 2025-05-14 - N+1 Query and I/O Bottlenecks
**Learning:** Found two major performance anti-patterns:
1. N+1 Database queries in `dressColor` method: Looping through characters and executing a query for each character. Fixed by using a single batch fetch with `IN` clause and O(1) in-memory map lookup.
2. N+1 I/O system calls in image listing: Using `filesize()` and `filemtime()` separately in a loop. Fixed by using a single `stat()` call per file.
3. Robustness vs Micro-optimization: `glob()` with `GLOB_BRACE` is faster but can be less robust for case-insensitive matching compared to `scandir()` + `preg_match('/.../i')` on some filesystems. Chose robustness with a more targeted micro-optimization (`stat()`).
**Action:** Always look for loops containing database queries or multiple file system calls for easy performance wins.
