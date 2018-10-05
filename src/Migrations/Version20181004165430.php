<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181004165430 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE student_a (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, exam_id INT NOT NULL, question_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_F44D6EC0CB944F1A (student_id), INDEX IDX_F44D6EC0578D5E91 (exam_id), INDEX IDX_F44D6EC01E27F6BF (question_id), INDEX IDX_F44D6EC0AA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_a ADD CONSTRAINT FK_F44D6EC0CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student_a ADD CONSTRAINT FK_F44D6EC0578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE student_a ADD CONSTRAINT FK_F44D6EC01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE student_a ADD CONSTRAINT FK_F44D6EC0AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE student_a');
    }
}
