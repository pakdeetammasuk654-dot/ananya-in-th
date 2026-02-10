# Bolt's Performance Journal

## 2025-05-22 - [Batch Query Optimization for N+1 Problem]
**Learning:** Found that the `dressColor` method was performing a database query for every character in the input string. In a legacy codebase like this, many methods still follow the pattern of looping over inputs and querying the database individually. Manual verification is critical because even if a memory suggests an optimization was made, the actual code may still contain the bottleneck due to state loss or incomplete application.
**Action:** Always verify the actual source code even if memories claim an optimization exists. Use `IN` clauses for batch queries and map results back to the original order using a hash map to preserve sequence and handle duplicates.
