PHP sandbox
===========

#### Database structure:

The `users` table contains data about users.
The `user_accounts` table contains a list of user accounts, where each user can have multiple accounts.
The `transactions` table contains information about the transfer of funds from one account to another.
Transactions can be between accounts of different users and between different accounts of the same user.

#### Task:

Using PHP, implement the display of payment statistics for the selected user by month.
Payment statistics are displayed for the selected user for each calendar month and must contain the following information:
1. Monthly balance - the sum of all incoming transactions on all user accounts minus the sum of all outgoing transactions on all user accounts for the calendar month;
2. Number of transactions - the number of records in the transaction table for the user for the calendar month.
   Particular attention should be paid to the fact that transactions between accounts of the same user should not affect the balance, but should be taken into account in the number of transactions.

#### Appearance:

The user must be selected from a drop-down list. The drop-down list should display only those users who have transactions.
The result should be displayed as a table, where the first column displays the month, the second - the monthly balance, and the third - the number of transactions.

#### Requirements:

1. Requests to the backend must be sent without reloading the page;
2. The code must be written without using frameworks;
3. The code must be written clearly and neatly, with tabulation and other writing elements, without unnecessary elements and functions that are not related to the functionality of the test task, provided with clear comments
4. To calculate balances and the number of transactions, SQL queries must be used, not loops in the code.
