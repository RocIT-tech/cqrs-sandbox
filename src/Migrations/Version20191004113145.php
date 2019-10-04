<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004113145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Init';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE tetris_game (tetris_game_id VARCHAR(40) NOT NULL, date DATE NOT NULL, PRIMARY KEY(tetris_game_id))');
        $this->addSql('COMMENT ON COLUMN tetris_game.date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE person (person_id VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(person_id))');
        $this->addSql('CREATE TABLE challenger (challenger_id VARCHAR(40) NOT NULL, tetris_game_id VARCHAR(40) NOT NULL, person_id VARCHAR(40) NOT NULL, rank SMALLINT NOT NULL, PRIMARY KEY(challenger_id))');
        $this->addSql('CREATE INDEX IDX_70B29776A19F4AD6 ON challenger (tetris_game_id)');
        $this->addSql('CREATE INDEX IDX_70B29776217BBB47 ON challenger (person_id)');
        $this->addSql('CREATE UNIQUE INDEX challenger_tetris_person_rank_unique_index ON challenger (tetris_game_id, person_id, rank)');
        $this->addSql('ALTER TABLE challenger ADD CONSTRAINT FK_70B29776A19F4AD6 FOREIGN KEY (tetris_game_id) REFERENCES tetris_game (tetris_game_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE challenger ADD CONSTRAINT FK_70B29776217BBB47 FOREIGN KEY (person_id) REFERENCES person (person_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE challenger DROP CONSTRAINT FK_70B29776A19F4AD6');
        $this->addSql('ALTER TABLE challenger DROP CONSTRAINT FK_70B29776217BBB47');
        $this->addSql('DROP TABLE tetris_game');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE challenger');
    }
}
