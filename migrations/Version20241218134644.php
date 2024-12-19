<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218134644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dislikeCom (user_id INT NOT NULL, commentaire_id INT NOT NULL, INDEX IDX_CBD40D8AA76ED395 (user_id), INDEX IDX_CBD40D8ABA9CD190 (commentaire_id), PRIMARY KEY(user_id, commentaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dislikeCom ADD CONSTRAINT FK_CBD40D8AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dislikeCom ADD CONSTRAINT FK_CBD40D8ABA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dislikeCom DROP FOREIGN KEY FK_CBD40D8AA76ED395');
        $this->addSql('ALTER TABLE dislikeCom DROP FOREIGN KEY FK_CBD40D8ABA9CD190');
        $this->addSql('DROP TABLE dislikeCom');
    }
}
