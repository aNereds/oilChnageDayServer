<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230306211639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE mileage (id INT AUTO_INCREMENT NOT NULL,
            vehicle_id INT NOT NULL, UNIQUE INDEX UNIQ_56BDF814545317D1 (vehicle_id),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL,
            owner_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            year DATE NOT NULL,
            INDEX IDX_USER_ID (owner_id),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE mileage ADD CONSTRAINT FK_MILEAGE_ID_TO_VEHICLE FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_VEHICLE_TO_USER FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE mileage DROP FOREIGN KEY FK_MILEAGE_ID_TO_VEHICLE');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_VEHICLE_TO_USER');
        $this->addSql('DROP TABLE mileage');
        $this->addSql('DROP TABLE vehicle');
    }
}
