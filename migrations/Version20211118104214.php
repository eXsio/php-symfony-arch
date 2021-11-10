<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Infrastructure\Doctrine\Migrations\DoctrineMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118104214 extends DoctrineMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE POST_HEADERS (id BLOB NOT NULL --(DC2Type:ulid)
        , title VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, commentsCount INTEGER NOT NULL, tags CLOB NOT NULL --(DC2Type:json)
        , createdAt DATETIME NOT NULL, version INTEGER NOT NULL, userId BLOB DEFAULT NULL --(DC2Type:ulid)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A6AFD3F064B64DCC ON POST_HEADERS (userId)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE POST_HEADERS');
    }

    protected function getDbName(): string
    {
        return "security";
    }
}
