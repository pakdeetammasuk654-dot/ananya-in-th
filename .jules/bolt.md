## 2025-05-23 - [N+1 Query in dressColor]
**Learning:** The `dressColor` method was performing a database query for every character in the input string. Since the target table (`colortb`) is static and small (7-8 rows), fetching it once and using in-memory mapping is significantly faster.
**Action:** Always check for database queries inside loops that iterate over user input strings or arrays, especially when the target data is small and finite.
