<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113125551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_commentaire (user_id INT NOT NULL, commentaire_id INT NOT NULL, INDEX IDX_CEEBA129A76ED395 (user_id), INDEX IDX_CEEBA129BA9CD190 (commentaire_id), PRIMARY KEY(user_id, commentaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_categorie (video_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_8B02ABA129C1004E (video_id), INDEX IDX_8B02ABA1BCF5E72D (categorie_id), PRIMARY KEY(video_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_commentaire ADD CONSTRAINT FK_CEEBA129A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_commentaire ADD CONSTRAINT FK_CEEBA129BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_categorie ADD CONSTRAINT FK_8B02ABA129C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_categorie ADD CONSTRAINT FK_8B02ABA1BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video ADD createur_id INT NOT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C73A201E5 FOREIGN KEY (createur_id) REFERENCES createur_contenu (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C73A201E5 ON video (createur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_commentaire DROP FOREIGN KEY FK_CEEBA129A76ED395');
        $this->addSql('ALTER TABLE user_commentaire DROP FOREIGN KEY FK_CEEBA129BA9CD190');
        $this->addSql('ALTER TABLE video_categorie DROP FOREIGN KEY FK_8B02ABA129C1004E');
        $this->addSql('ALTER TABLE video_categorie DROP FOREIGN KEY FK_8B02ABA1BCF5E72D');
        $this->addSql('DROP TABLE user_commentaire');
        $this->addSql('DROP TABLE video_categorie');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C73A201E5');
        $this->addSql('DROP INDEX IDX_7CC7DA2C73A201E5 ON video');
        $this->addSql('ALTER TABLE video DROP createur_id');
    }
}
