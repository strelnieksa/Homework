Mintos Homework - CRUD API Testing Setup

Prerequisites

PHP (Check with php -v)
*Download & install from: https://www.php.net/downloads or use https://www.apachefriends.org/download.html
*For macOS recommended: brew install php

Composer (Check with composer -V)
*Download from: https://getcomposer.org/

Git (for cloning the repo, check with git --version)
*Download from: https://git-scm.com/downloads

Codeception (Check with codecept --version)
*Install using Composer: composer global require codeception/codeception
*If there are some problems with codeception installation via composer in php.ini file uncomment ;extension=zip


Setup Instructions

Clone the Repository to project folder: git clone https://github.com/strelnieksa/Mintos-Homework.git
*Open the project in your preferred IDE (Example: VS Code(Install extensions: PHP Intelephense, PHP Debug), PhpStorm) 

Running API Tests for CRUD operations---
*Open a terminal window and navigate to the root directory of your project. Start a PHP built-in web server: php -S localhost:8080 -t public
*Open a new terminal window or tab (do not close the one running the PHP server) and run the following command to execute the API tests: vendor/bin/codecept run Api
*Or use command: php vendor/bin/codecept run Api if vendor/bin/codecept is not executable or your system restricts direct script execution

Results
*Test results are written into database.sqlite file
*It is recommended to use DB Browser for SQLite (DB4S) for the GUI. Download from: https://sqlitebrowser.org/dl/

Database
*If there are some problems running tests with sqlite database in php.ini file uncomment ;extension=sqlite3 and ;extension=pdo_sqlite

*Database already have tables set up with validation:
	*User

CREATE TABLE User (
    id TEXT PRIMARY KEY UNIQUE,
    firstName TEXT NOT NULL CHECK(length(firstName) >= 2 AND length(firstName) <= 50),
    lastName TEXT NOT NULL CHECK(length(lastName) >= 2 AND length(lastName) <= 50),
    email TEXT CHECK(email LIKE '%@%.%'),
    dateOfBirth TEXT NOT NULL CHECK(
        dateOfBirth LIKE '____-__-__' 
        AND CAST(SUBSTR(dateOfBirth, 6, 2) AS INTEGER) BETWEEN 1 AND 12  
        AND CAST(SUBSTR(dateOfBirth, 9, 2) AS INTEGER) BETWEEN 1 AND 31),
    documentId TEXT NOT NULL CHECK(length(documentId) >= 5 AND length(documentId) <= 20),
    countryOfIssue TEXT NOT NULL CHECK(
        length(countryOfIssue) = 2 AND 
        countryOfIssue = UPPER(countryOfIssue) AND
        countryOfIssue IN (
            'AF', 'AL', 'DZ', 'AS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ', 'BS', 'BH', 'BD', 
            'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BA', 'BW', 'BR', 'IO', 'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 
            'CA', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO', 'KM', 'CG', 'CD', 'CK', 'CR', 'HR', 'CU', 'CW', 
            'CY', 'CZ', 'CI', 'DK', 'DJ', 'DM', 'DO', 'EC', 'EG', 'SV', 'GQ', 'ER', 'EE', 'ET', 'FK', 'FO', 'FJ', 'FI', 
            'FR', 'GA', 'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GU', 'GT', 'GG', 'GN', 'GW', 'GY', 'HT', 
            'HM', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IL', 'IT', 'JM', 'JP', 'JE', 'JO', 'KZ', 'KE', 
            'KI', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MW', 'MY', 'MV', 'ML', 
            'MT', 'MH', 'MQ', 'MR', 'MU', 'YT', 'MX', 'FM', 'MD', 'MC', 'MN', 'ME', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 
            'NP', 'NL', 'NC', 'NZ', 'NI', 'NE', 'NG', 'NU', 'NF', 'KP', 'NO', 'OM', 'PK', 'PW', 'PA', 'PG', 'PY', 'PE', 
            'PH', 'PN', 'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'BL', 'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS', 
            'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'KR', 'ES', 'LK', 'SD', 
            'SR', 'SS', 'SJ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TL', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 
            'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'US', 'UY', 'UZ', 'VU', 'VA', 'VE', 'VN', 'WF', 'EH', 'YE', 'ZM', 'ZW'
        )
    ),
    validUntil TEXT NOT NULL CHECK(validUntil LIKE '____-__-__')
)

	*ProblemDetails

CREATE TABLE ProblemDetails (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT,
    title TEXT NOT NULL,
    status INTEGER NOT NULL,
    detail TEXT,
    instance TEXT
)

Mentions:
*Username = admin
*Password = P@ssw0rd