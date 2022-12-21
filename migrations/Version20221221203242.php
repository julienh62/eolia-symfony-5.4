<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221221203242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_seance DROP FOREIGN KEY FK_412FF5B558FBEB9');
        $this->addSql('ALTER TABLE purchase_seance DROP FOREIGN KEY FK_412FF5BE3797A94');
        $this->addSql('DROP TABLE purchase_seance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_seance (purchase_id INT NOT NULL, seance_id INT NOT NULL, INDEX IDX_412FF5B558FBEB9 (purchase_id), INDEX IDX_412FF5BE3797A94 (seance_id), PRIMARY KEY(purchase_id, seance_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE purchase_seance ADD CONSTRAINT FK_412FF5B558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE purchase_seance ADD CONSTRAINT FK_412FF5BE3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
