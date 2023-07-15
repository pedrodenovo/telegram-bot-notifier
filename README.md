# telegram-bot-notifier
This PHP code sends a message using a Telegram bot. It connects to a MySQL database, checks for new items based on a filter, and avoids sending duplicate messages. The code is configurable with token, chat ID, and database credentials.

1. Setting initial configurations:
   - `$botToken`: This variable stores the access key (token) for your Telegram bot. Replace `'SEU_TOKEN_AQUI'` with the actual token.
   - `$chatId`: Here, you should provide the chat ID to which you want to send the message. Replace `'ID_DO_CHAT_AQUI'` with the actual ID.
   - `$host`, `$dbname`, `$user`, and `$pass`: These variables store the MySQL database access information. Replace the values with the actual data of your database.

2. Connecting to the database:
   - The code establishes a connection to the MySQL database using the PDO extension.
   - The database access information is passed to the `PDO()` function to create a new instance of the PDO class.
   - In case of a connection error, a `PDOException` exception is thrown, and an error message is displayed.

3. Checking for a new item in the table:
   - The SQL query is defined in the `$query` variable, where you should replace `'sua_tabela'` with the actual table name.
   - The code prepares the query using the `prepare()` function of the PDO instance and executes it using `execute()`.
   - The result of the query is retrieved using `fetch(PDO::FETCH_ASSOC)`. The item values (e.g., `id`, `campo1`, `campo2`) are stored in variables for later use.

4. Checking if the item has already been sent:
   - A new query is made to check if the item ID already exists in the `itens_enviados` table.
   - The row count is obtained using `fetchColumn()`.
   - If the count is zero, it means the item has not been sent yet, and the code proceeds to send the message and register the ID in the `itens_enviados` table.
   - Otherwise, a message is displayed indicating that the item has already been sent previously.

5. Sending the message:
   - The message to be sent to the chat is defined in the `$message` variable.
   - The Telegram API URL is constructed based on the bot token and stored in `$apiUrl`.
   - The message parameters (such as the chat ID and the message text) are stored in `$params`.
   - A cURL request is initiated with `curl_init()` and configured with relevant options like the URL, parameters, and returning the result.
   - The request is executed with `curl_exec()`, and the response is obtained.
   - If the response is false, an error message is displayed. Otherwise, a success message is displayed, and the item ID is registered in the `itens_enviados` table.

Remember to replace the placeholder values with actual information, such as tokens, chat IDs, table names, etc. Additionally, it's important to ensure that the cURL and PDO extensions are enabled on the server where the code will be executed.

After understanding and customizing the code according to your needs, you can add the PHP file to your GitHub repository, along with any other project-related structure or files.
