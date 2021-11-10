<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Infrastructure\Doctrine\Migrations\DoctrineMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211121154003 extends DoctrineMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE POSTS (id BLOB NOT NULL --(DC2Type:ulid)
        , title VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, tags CLOB NOT NULL --(DC2Type:json)
        , createdById BLOB NOT NULL --(DC2Type:ulid)
        , createdByName VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, deletedAt DATETIME, version INTEGER DEFAULT 1 NOT NULL
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE POST_COMMENTS (postId BLOB NOT NULL --(DC2Type:ulid)
        , commentsCount INTEGER NOT NULL, comments CLOB NOT NULL --(DC2Type:json)
        , PRIMARY KEY(postId))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE POSTS');
        $this->addSql('DROP TABLE POST_COMMENTS');
    }

    protected function getDbName(): string
    {
        return "posts";
    }
}
