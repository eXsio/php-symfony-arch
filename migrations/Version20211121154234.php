<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Infrastructure\Doctrine\Migrations\DoctrineMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211121154234 extends DoctrineMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE POST_HEADERS (id BLOB NOT NULL --(DC2Type:ulid)
        , title VARCHAR(255) NOT NULL,  tags CLOB NOT NULL --(DC2Type:json)
        , version INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE COMMENTS (id BLOB NOT NULL --(DC2Type:ulid)
        , author VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, parentId BLOB DEFAULT NULL --(DC2Type:ulid)
        , postId BLOB DEFAULT NULL --(DC2Type:ulid)
        , PRIMARY KEY(id)
        , FOREIGN KEY (postId) REFERENCES POST_HEADERS(id))');
        $this->addSql('CREATE INDEX IDX_C1 ON COMMENTS (parentId)');
        $this->addSql('CREATE INDEX IDX_C2 ON COMMENTS (postId)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE COMMENTS');
        $this->addSql('DROP TABLE POST_HEADERS');
    }

    protected function getDbName(): string
    {
        return "comments";
    }
}
