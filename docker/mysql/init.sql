CREATE TABLE IF NOT EXISTS planos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    velocidade_mbps SMALLINT UNSIGNED NOT NULL,
    valor_mensal DECIMAL(10,2) NOT NULL,
    dia_vencimento TINYINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cpf CHAR(11) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    plano_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_clientes_cpf (cpf),
    CONSTRAINT fk_clientes_plano_id FOREIGN KEY (plano_id) REFERENCES planos (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS faturas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NOT NULL,
    competencia CHAR(7) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_vencimento DATE NOT NULL,
    status ENUM('aberta', 'parcial', 'paga', 'vencida') NOT NULL DEFAULT 'aberta',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_faturas_cliente_id FOREIGN KEY (cliente_id) REFERENCES clientes (id) ON DELETE CASCADE,
    KEY idx_faturas_data_vencimento (data_vencimento),
    KEY idx_faturas_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pagamentos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fatura_id INT UNSIGNED NOT NULL,
    valor_pago DECIMAL(10,2) NOT NULL,
    data_pagamento DATE NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pagamentos_fatura_id FOREIGN KEY (fatura_id) REFERENCES faturas (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
