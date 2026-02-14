-- Créer un administrateur directement dans MySQL
INSERT INTO users (login, password, role, actif, created_at, updated_at) 
VALUES (
    'ADMIN001', 
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- hash de 'Admin@2024!'
    'Radmin', 
    1, 
    NOW(), 
    NOW()
);

-- Vérification
SELECT * FROM users WHERE login = 'ADMIN001';
