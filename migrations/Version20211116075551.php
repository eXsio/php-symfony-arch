<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Infrastructure\Doctrine\Migrations\DoctrineMigration;
use Doctrine\DBAL\Schema\Schema;

final class Version20211116075551 extends DoctrineMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE USERS (id BLOB NOT NULL --(DC2Type:ulid)
        , email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, version INTEGER DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3D76759E7927C74 ON USERS (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE USERS');
    }

    protected function getDbName(): string
    {
        return "security";
    }
}
