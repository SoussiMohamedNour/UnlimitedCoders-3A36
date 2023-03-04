/* Liste utilisateur*/
INSERT INTO `utilisateur` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `age`, `sexe`, `num_tel`, `cin`, `is_verified`, `image_name`, `image_size`, `isbanned`, `updated_at`) VALUES
(100, 'AdminHealthified@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$1z2LV70K0vqIxyHmpqCMee4q.uYX.NAm5f0UsmG4uGKSFCzUdL7dC', 'Admin', 'Healthified', 19, 'male', '23889776', '1433528', 1, 'screenshot-2023-02-27-at-11-08-59-63fd192283191606493520.png', 1573536, NULL, '2023-02-27 20:57:06'),
(101, 'ahmed.ridha@esprit.tn', '[]', '$2y$13$tHL.d5ZvR7/CqS48EoyqI.WTX7kNwkuHXbAMZG.Be.jho.YJOD.c6', 'Ahmed', 'Ridha', 18, 'male', '23456789', '667755', 0, 'screenshot-2023-02-23-at-11-10-26-63ff1e80e36ef233808273.png', 36603, NULL, '2023-03-01 09:44:32'),
(102, 'mootaz.benfarhat@esprit.tn', '[]', '$2y$13$HINimr7nCGosRejRnm71EO/8KCPnepf6qXWhHJqIXnt7WU6hIEUlG', 'Ben Farhat', 'Mootaz', 23, 'male', '2334556', '14335258', 1, NULL, NULL, 0, NULL),
(103, 'mouhebnaddari@esprit.tn', '[]', '$2y$13$gxVSpcjrwwwpUiJUlQ6aJO3j0d/oOPm5fQyXz96VgzXqt8uBhFVpK', 'Mouheb', 'Naddari', 18, 'male', '98990987', '223378', 0, NULL, NULL, NULL, NULL),
(104, 'mouheb.naddari@esprit.tn', '[]', '$2y$13$v67rmWvf97xpQKhOP6p9q.Jw.tHSt4SnlRuDGDor0oxIr7ZeaKLI.', 'Naddari', 'Mouheb', 97, 'male', '71111223', '667788', 0, NULL, NULL, 1, NULL),
(105, 'ayaghattas606@gmail.com', '[]', '$2y$13$k5JamNpgNp6VbxQAk4tJ7Oq4BhkNhNbeGgkwIng3KcKcuzZO/vJqm', 'ghattas', 'aya', 23, 'female', '22334455', '2233445', 0, NULL, NULL, 1, NULL),
(106, 'amineallah.mekni@esprit.tn', '[]', '$2y$13$T3VDqC7Rzd6ZTcEmkTrRQuZahYveDAboVWYjaAeoFBLTQKvumto2a', 'Amine', 'Allah', 27, 'male', '23232323', '667722', 0, NULL, NULL, NULL, NULL);

/* Liste Médeicaments*/
INSERT INTO `medicament` (`id`, `nom`, `dosage`, `prix`, `description`) VALUES
(1, 'Doliprane', 2, 4, 'Antalgiques -Antipyrétiques -Antispasmodiques > Antalgiques non opioïdes >\r\nAntalgiques non opioïdes seuls > Paracétamol\r\n(Formes sèches)'),
(2, 'Fervex', 3, 9, 'Ce médicament associe du paracétamol, qui a une action antipyrétique et antalgique, un antihistaminique qui a un effet asséchant sur les sécrétions nasales, et de la vitamine C'),
(3, 'Maxilase', 3, 10.5, 'Ce médicament contient une enzyme destinée à lutter contre l\'œdème et l\'inflammation.\r\nIl est utilisé dans le traitement d\'appoint de l\'inflammation aiguë de la gorge.'),
(4, 'Gripex', 4, 20, 'traitement symptomatique des affections orl\r\naiguës : rhumes, rhinites , rhinopharyngites .\r\ntraitement symptomatique des états grippaux.\r\nen cas d’affection bactérienne, une antibiothérapie peut être nécessaire.'),
(5, 'Spasfon', 3, 12, 'Ce médicament est un antispasmodique. Il lutte contre les contractions anormales et douloureuses de l\'intestin, des voies biliaires, des voies urinaires et de l\'utérus.\r\nIl est utilisé dans le traitement des spasmes douloureux d\'origines digestive'),
(6, 'HEXOMEDINE', 6, 10, 'Ce médicament est un antiseptique local.Il est utilisé pour assurer l\'antisepsie des lésions de la peau infectées ou exposées à un risque d\'infection.'),
(7, 'Pourvuna', 3, 20, 'couldina contient le même type d\'ingrédients actifs que le frénadol: acide acétylsalicylique (pour l\'inconfort général), chlorphénamine (pour les allergies) et phényléphrine (pour la congestion). Il se prend sous forme de comprimés effervescents.'),
(8, 'Dolmen', 1, 30, 'Ce médicament est l\'un des plus anciens et des plus connus: il contient de l\'Acide Acétylsalicylique, de la Vitamine C et de la Codéine (puissant antitussif). Soyez prudent car la prise de codéine, qui est un dérivé de la morphine');