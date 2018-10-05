<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181004112805 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exam ADD student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C6CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_38BBA6C6CB944F1A ON exam (student_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C6CB944F1A');
        $this->addSql('DROP INDEX IDX_38BBA6C6CB944F1A ON exam');
        $this->addSql('ALTER TABLE exam DROP student_id');
    }
}
