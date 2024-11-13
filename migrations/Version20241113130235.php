<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113130235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE likeCom (user_id INT NOT NULL, commentaire_id INT NOT NULL, INDEX IDX_BF21BF04A76ED395 (user_id), INDEX IDX_BF21BF04BA9CD190 (commentaire_id), PRIMARY KEY(user_id, commentaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE likeCom ADD CONSTRAINT FK_BF21BF04A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likeCom ADD CONSTRAINT FK_BF21BF04BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_commentaire DROP FOREIGN KEY FK_CEEBA129BA9CD190');
        $this->addSql('ALTER TABLE user_commentaire DROP FOREIGN KEY FK_CEEBA129A76ED395');
        $this->addSql('DROP TABLE user_commentaire');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_commentaire (user_id INT NOT NULL, commentaire_id INT NOT NULL, INDEX IDX_CEEBA129BA9CD190 (commentaire_id), INDEX IDX_CEEBA129A76ED395 (user_id), PRIMARY KEY(user_id, commentaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_commentaire ADD CONSTRAINT FK_CEEBA129BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_commentaire ADD CONSTRAINT FK_CEEBA129A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likeCom DROP FOREIGN KEY FK_BF21BF04A76ED395');
        $this->addSql('ALTER TABLE likeCom DROP FOREIGN KEY FK_BF21BF04BA9CD190');
        $this->addSql('DROP TABLE likeCom');
    }
}
