<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240311150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD agent_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64946EAB62F FOREIGN KEY (agent_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64946EAB62F ON user (agent_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64946EAB62F');
        $this->addSql('DROP INDEX IDX_8D93D64946EAB62F ON user');
        $this->addSql('ALTER TABLE user DROP agent_id_id');
    }
}
