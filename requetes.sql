SELECT class.class_id, class.name, teacher_class.year_id, teacher.teacher_id, teacher.name FROM class 
LEFT JOIN teacher_class ON teacher_class.class_id = class.class_id 
AND (
        CASE 
            WHEN teacher_class.year_id=9 THEN 1
            WHEN teacher_class.etat_teacher_class='actif' THEN 1
            WHEN teacher_class.status_teacher_class=0 THEN 1
            ELSE 0 
        END
) = 1
LEFT JOIN teacher ON teacher.teacher_id = teacher_class.teacher_id
AND (
        CASE
            WHEN teacher.etat_teacher='acif' THEN 1
            WHEN teacher.status_teacher=0 THEN 1
            ELSE 0
        END
) = 1
INNER JOIN cycle ON cycle.cycle_id=class.cycle_id
AND (
        CASE
            WHEN cycle.etat_cycle='actif' THEN 1
            WHEN cycle.status_cycle=0 THEN 1
            ELSE 0
        END
) = 1
INNER JOIN session ON session.session_id=class.session_id
AND (
        CASE
            WHEN session.etat_session='actif' THEN 1
            WHEN session.status_session=0 THEN 1
            ELSE 0
        END
) = 1
INNER JOIN school ON school.school_id=class.school_id
AND (
        CASE
            WHEN school.etat_school='actif' THEN 1
            WHEN school.status_school=0 THEN 1
            ELSE 0
        END
) = 1
WHERE class.etat_class = 'actif' 
AND class.status_class = 0
AND class.session_id = 1
AND class.cycle_id = 4
AND class.school_id = 2









CREATE TABLE `montant_scolarite` (
	`montant_scolarite_id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`montant` TEXT NOT NULL,
	`class_id` INT NOT NULL,
	`year_id` INT NOT NULL,
	`id_user` INT NOT NULL,
	`status_montant_scolarite` INT NOT NULL,
	`etat_montant_scolarite` TEXT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	`deleted_at` TIMESTAMP NULL,
	CONSTRAINT `montant_scolarite_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`),
	CONSTRAINT `montant_scolarite_id_year_forein` FOREIGN KEY (`year_id`) REFERENCES `year`(`year_id`),
	CONSTRAINT `montant_scolarite_id_class_foreign` FOREIGN KEY (`class_id`) REFERENCES `class`(`class_id`)
);










INNER JOIN teacher_school ON teacher.teacher_id = teacher_school.teacher_id
INNER JOIN school ON teacher_school.school_id = teacher_school.school_id
LEFT JOIN teacher_class ON teacher_class.teacher_id = teacher.teacher_id
LEFT JOIN class ON class.class_id = teacher_class.class_id























CREATE TABLE `inscription` (
	`inscription_id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`class_id` TEXT NOT NULL,
	`id_user` INT NOT NULL,
	`student_id` INT NOT NULL,
	`amount` INT NOT NULL,
	`status_ins` INT NOT NULL,
	`etat_ins` TEXT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	`deleted_at` TIMESTAMP NULL,
	CONSTRAINT `cycle_id_usder_foreign` FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`),
	CONSTRAINT `cycle_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user`(`id_user`),
	CONSTRAINT `class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `class`(`class_id`)
)


CREATE TABLE "teacher_class" (
	"teacher_class_id"	INTEGER,
	"class_id"	TEXT NOT NULL,
	"teacher_id"	TEXT NOT NULL,
	"year_id"	INT NOT NULL,
	"user_id"	INT NOT NULL,
	"etat_teacher_school"	TEXT NOT NULL,
	"status_teacher_school"	TEXT NOT NULL,
	"created_at"	TIMESTAMP,
	"updated_at"	TIMESTAMP,
	"deleted_at"	TIMESTAMP,
	CONSTRAINT "class_id_foreign" FOREIGN KEY("class_id") REFERENCES "class"("class_id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "teacher_id_foreign" FOREIGN KEY("teacher_id") REFERENCES "teacher"("teacher_id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "year_id_id_foreign" FOREIGN KEY("year_id") REFERENCES "year"("year_id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "user_id_id_foreign" FOREIGN KEY("user_id") REFERENCES "user"("user_id") ON UPDATE CASCADE ON DELETE CASCADE,
	PRIMARY KEY("teacher_class_id" AUTOINCREMENT)
)


CREATE TABLE `bourse` (
	`bourse_id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`year_id` INT NOT NULL,
	`name` TEXT NOT NULL,
	`description` TEXT NOT NULL,
	`amount` INT NOT NULL,
	`status` INT NOT NULL,
	`etat` TEXT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	`deleted_at` TIMESTAMP NULL,
	CONSTRAINT `year_id_foreign` FOREIGN KEY (`year_id`) REFERENCES `year`(`year_id`),
)



CREATE TABLE `bourse_student` (
	`bourse_student_id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`session_id` INT NOT NULL,
	`cycle_id` INT NOT NULL,
	`class_id` INT NOT NULL,
	`student_id` INT NOT NULL,
	`year_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`bourse_id` INT NOT NULL,
	`status` INT NOT NULL,
	`etat` TEXT NOT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	`deleted_at` TIMESTAMP NULL,

	CONSTRAINT `session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `session`(`session_id`),
	CONSTRAINT `cycle_id_foreign` FOREIGN KEY (`cycle_id`) REFERENCES `cycle`(`cycle_id`),
	CONSTRAINT `class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `class`(`class_id`),
	CONSTRAINT `student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`),
	CONSTRAINT `year_id_foreign` FOREIGN KEY (`year_id`) REFERENCES `year`(`year_id`),
	CONSTRAINT `user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`),
	CONSTRAINT `bourse_id_foreign` FOREIGN KEY (`bourse_id`) REFERENCES `bourse`(`bourse_id`)
)