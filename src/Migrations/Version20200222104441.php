<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200222104441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, district_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, objectif LONGTEXT DEFAULT NULL, resultat LONGTEXT DEFAULT NULL, annee VARCHAR(10) NOT NULL, date_debut VARCHAR(10) NOT NULL, date_fin VARCHAR(10) NOT NULL, lieu VARCHAR(255) NOT NULL, rmo VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, flag INT DEFAULT NULL, INDEX IDX_B8755515B08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activite_participant (activite_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_F7AC1E3C9B0F88B1 (activite_id), INDEX IDX_F7AC1E3C9D1C3019 (participant_id), PRIMARY KEY(activite_id, participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activite_departement (activite_id INT NOT NULL, departement_id INT NOT NULL, INDEX IDX_E1452E4E9B0F88B1 (activite_id), INDEX IDX_E1452E4ECCF9E01E (departement_id), PRIMARY KEY(activite_id, departement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE activite_participant ADD CONSTRAINT FK_F7AC1E3C9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite_participant ADD CONSTRAINT FK_F7AC1E3C9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite_departement ADD CONSTRAINT FK_E1452E4E9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activite_departement ADD CONSTRAINT FK_E1452E4ECCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activite_participant DROP FOREIGN KEY FK_F7AC1E3C9B0F88B1');
        $this->addSql('ALTER TABLE activite_departement DROP FOREIGN KEY FK_E1452E4E9B0F88B1');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE activite_participant');
        $this->addSql('DROP TABLE activite_departement');
    }
}
