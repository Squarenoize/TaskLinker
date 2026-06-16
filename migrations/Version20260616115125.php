<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260616115125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project CHANGE archive_date archive_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE project_worker ADD CONSTRAINT FK_88165428166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_worker ADD CONSTRAINT FK_881654286B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE status RENAME INDEX idx_7b00651c6c1197c9 TO IDX_7B00651C166D1F9C');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id)');
        $this->addSql('ALTER TABLE task RENAME INDEX idx_527edb256c1197c9 TO IDX_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE task RENAME INDEX idx_527edb25881ecfa7 TO IDX_527EDB256BF700BD');
        $this->addSql('ALTER TABLE task RENAME INDEX idx_527edb2563e33a83 TO IDX_527EDB256B20BA36');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F048DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F04BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F76B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id)');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F78DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE timeslot RENAME INDEX idx_3be452f763e33a83 TO IDX_3BE452F76B20BA36');
        $this->addSql('ALTER TABLE timeslot RENAME INDEX idx_3be452f7b8e08577 TO IDX_3BE452F78DB60186');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F76B20BA36');
        $this->addSql('ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F78DB60186');
        $this->addSql('ALTER TABLE timeslot RENAME INDEX idx_3be452f76b20ba36 TO IDX_3BE452F763E33A83');
        $this->addSql('ALTER TABLE timeslot RENAME INDEX idx_3be452f78db60186 TO IDX_3BE452F7B8E08577');
        $this->addSql('ALTER TABLE project CHANGE archive_date archive_date DATE NOT NULL');
        $this->addSql('ALTER TABLE status DROP FOREIGN KEY FK_7B00651C166D1F9C');
        $this->addSql('ALTER TABLE status RENAME INDEX idx_7b00651c166d1f9c TO IDX_7B00651C6C1197C9');
        $this->addSql('ALTER TABLE task_tag DROP FOREIGN KEY FK_6C0B4F048DB60186');
        $this->addSql('ALTER TABLE task_tag DROP FOREIGN KEY FK_6C0B4F04BAD26311');
        $this->addSql('ALTER TABLE project_worker DROP FOREIGN KEY FK_88165428166D1F9C');
        $this->addSql('ALTER TABLE project_worker DROP FOREIGN KEY FK_881654286B20BA36');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256BF700BD');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256B20BA36');
        $this->addSql('ALTER TABLE task RENAME INDEX idx_527edb256b20ba36 TO IDX_527EDB2563E33A83');
        $this->addSql('ALTER TABLE task RENAME INDEX idx_527edb25166d1f9c TO IDX_527EDB256C1197C9');
        $this->addSql('ALTER TABLE task RENAME INDEX idx_527edb256bf700bd TO IDX_527EDB25881ECFA7');
    }
}
