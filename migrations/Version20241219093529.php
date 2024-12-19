<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219093529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC29C1004E');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC29C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('ALTER TABLE likeCom DROP FOREIGN KEY FK_BF21BF04A76ED395');
        $this->addSql('ALTER TABLE likeCom DROP FOREIGN KEY FK_BF21BF04BA9CD190');
        $this->addSql('ALTER TABLE likeCom ADD CONSTRAINT FK_BF21BF04A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likeCom ADD CONSTRAINT FK_BF21BF04BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C73A201E5');
        $this->addSql('ALTER TABLE video ADD likes_count INT NOT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C73A201E5 FOREIGN KEY (createur_id) REFERENCES createur_contenu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC29C1004E');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC29C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likeCom DROP FOREIGN KEY FK_BF21BF04A76ED395');
        $this->addSql('ALTER TABLE likeCom DROP FOREIGN KEY FK_BF21BF04BA9CD190');
        $this->addSql('ALTER TABLE likeCom ADD CONSTRAINT FK_BF21BF04A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likeCom ADD CONSTRAINT FK_BF21BF04BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C73A201E5');
        $this->addSql('ALTER TABLE video DROP likes_count');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C73A201E5 FOREIGN KEY (createur_id) REFERENCES createur_contenu (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
