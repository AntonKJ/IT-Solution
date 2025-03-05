<?php

/**
 * Return list of users.
 */
function get_users($conn)
{
    // TODO: implement
    $users = $conn->query("SELECT * FROM users;")->fetchall();

    return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn)
{
    // TODO: implement
    // $transactions = $conn->query("SELECT * FROM transactions;")->fetchall();
    $balances = calculate_balances($user_id, $conn);

    return $balances;
}
/**
 * Calculate all sums of account balances
 */
function calculate_balances ($user_id, $conn) {
    // TODO: implement
    $accounts = $conn->query("SELECT * FROM user_accounts WHERE user_id = {$user_id};")->fetchall();
    $accounts_balances = [];

    /* CALCULATE FROM AND TO ACCOUNTS TRANSACTION */
    $conn->exec("
                DELETE FROM `accounts_balances`;
                DROP TABLE `accounts_balances`;");
    $conn->exec("
            CREATE TABLE IF NOT EXISTS accounts_balances (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                account_id INTEGER NOT NULL,
                count_transaction INTEGER NOT NULL,
                balance INTEGER NOT NULL,
                period TEXT NOT NULL,
                FOREIGN KEY (account_id) REFERENCES user_accounts(id)
            );");
    $conn->exec("
                INSERT INTO `accounts_balances` (`id`,`account_id`,`count_transaction`,`balance`, period)
            VALUES
              (10, 10, 0, 0, '0000-00-00 00:00:00'),
              (11, 11, 0, 0, '0000-00-00 00:00:00'),
              (12, 12, 0, 0, '0000-00-00 00:00:00'),
              (13, 13, 0, 0, '0000-00-00 00:00:00'),
              (14, 14, 0, 0, '0000-00-00 00:00:00'),
              (15, 15, 0, 0, '0000-00-00 00:00:00'),
              (16, 16, 0, 0, '0000-00-00 00:00:00'),
              (17, 17, 0, 0, '0000-00-00 00:00:00'),
              (18, 18, 0, 0, '0000-00-00 00:00:00');
        ");
    /* Create View if needed
    $sql = "
        DELETE FROM accounts_balances;
        DROP TABLE accounts_balances;
        DROP VIEW accounts_balances;
        CREATE VIEW accounts_balances AS SELECT ua.id as id, ua.id as account_id, 0 as count_transaction, 0 as balance
              FROM user_accounts ua;
    ";
    $conn->exec($sql);
    $conn->commit();*/

    foreach ($accounts as $id => $account){

        $conn->exec("
            UPDATE accounts_balances
            SET balance = balance - (
                SELECT SUM(amount)
                FROM transactions
                WHERE account_from = accounts_balances.account_id
            ),
                count_transaction = count_transaction + (
                    SELECT count(id)
                    FROM transactions
                    WHERE account_from = accounts_balances.account_id
            ),
                period =  (
                    SELECT MAX(trdate)
                    FROM transactions
                    WHERE account_from = accounts_balances.account_id
                )
            WHERE accounts_balances.account_id = {$account['id']};
        ");

        $conn->exec("
            UPDATE accounts_balances
            SET balance = balance + (
                SELECT SUM(amount)
                FROM transactions
                WHERE account_to = accounts_balances.account_id
            ),
                count_transaction = count_transaction + (
                    SELECT count(id)
                    FROM transactions
                    WHERE account_to = accounts_balances.account_id
                )
            WHERE accounts_balances.account_id = {$account['id']};
        ");

        $balances = $conn->query("SELECT * FROM accounts_balances WHERE account_id = {$account['id']} ");
        $accounts_balances[] =  $balances->fetch();
    }

    return $accounts_balances;
}