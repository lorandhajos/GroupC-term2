# Group C year 1 term 2
## Project Gemorskos

A NHLStenden student project in cooperation with an imaginary company called Gemorskos, an online newspaper company.

### Group members:
* Loránd Máté Hájos (Team Leader) lorand.hajos@student.nhlstenden.com
* Cadar Iustin Mihai (Co-Leader) iustin.cadar@student.nhlstenden.com
* Andrii Chumakov (Vice Leader) andrii.chumakov@student.nhlstenden.com 
* Anastasia Lukanova (Secretary) anastasia.lukanova@student.nhlstenden.com 
* Gabriel Guevara (Co-Secretary) gabriel.guevara.lopez@student.nhlstenden.com
* Marijn Veenstra (Team Member) marijn.veenstra1@student.nhlstenden.com
* Nicanor Martinez (Team Member) nicanor.martinez@student.nhlstenden.com

#### The following open source libraries are used by this application:
docker-lamp https://github.com/MLGRadish/docker-lamp/

Copyright (c) 2022 MLGRadish

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

### Getting started

This code has a ```docker-compose.yaml``` file. That means that you have to install docker and docker-compose first.

Make sure you create a ```.env``` file in the root directory.
```
DB_SERVER="DATABASE_TYPE"
DB_ROOT_USER="DATABASE_USERNAME"
DB_ROOT_PASSWORD="DATABASE_PASSWORD"
DB_NAME="DATABASE_NAME"
```

To run the project you need to use ```docker-compose```
```
docker-compose build
docker-compose up
```

You need to import the database structure into phpmyadmin.

1. Open ```localhost:8080``` in a web browser
2. Log into phpmyadmin using the credential you set in the ```.env``` file.
3. Click create a new database and name it ```gemorskos```
4. Click import and then select the database file.
5. Once the file is uploaded, click import.
