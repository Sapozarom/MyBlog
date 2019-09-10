# MyBlog

![Alt text](/public/img/logo.png)

## Intro

MyBlog is a  web app created with PHP and Symfony 4 framework. It's a simple CRUD with a user system that allows multiple authors to publish their articles that can be seen and commented by website visitors.

## Config

Follow this steps to run the app with PHP's built-in server:

1. Download all files from the [repository](https://github.com/Sapozarom/MyBlog)
2. Open *MyBlog* localization in console and run `composer update`
3. Configure database:
    - open *MyBlog/.env* in editor
    - edit line 28 according to your DB server  
    `DATABASE_URL=mysql://USER:PASSWORD@HOST:PORT/DATABASENAME`

    - create database in console  
    `php bin/console doctrine:database:create`

    - make a migration  
    `php bin/console make:migration`

    - execute the migration  
    `php bin/console doctrine:migration:migrate`

    - load data fixtures  
    `php bin/console doctrine:fixtures:load`
4. Run built-in server using `php bin/console server:run`
5. Run app in your browser

## About the app

Here you can find some info about testing, functionality and solutions I've used to build this app.

### Users

In the fixtures there are implemented 3 users for the testing purpose

1. Login/password: user3/user3 - basic user, allowed to add comments and use User Panel section
2. Login/password: user1/user1 - author, allowed to post articles, has some basic admin rights
3. Login/password: user2/user2 - admin,  can modify other authors posts and use Admin Panel

### Articles

Articles can be created by using the pen button next to your username in navigation bar or in user panel.

- **published/unpublished**  
When ceating article author can save it as **unpublished**, this article will be only visible to it's author. After changing it's status to **published** it will be available on homepage and visible to all website visitors.
- **archived**  
Archived articles are no longer visible on homepage, but visitors can find when looking by tag, author or in archive. Also adding new comments is blocked.

### Comments

To practise I've decided to write simple comment system. Adding comments is available only for logged users who can edit and delete them. Right now deleted comment is replaced with *[deleted by ...]* message.

### Tags

To every article authors can ascribe tags. All active tags are visible on homepage in TagCloud. Size of button in this cloud is related to tag popularity.
