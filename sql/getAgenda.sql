CREATE PROCEDURE getAgenda(
    IN telefone_filter VARCHAR(13),
    IN id_especialidade_filter INT
)
BEGIN
    -- Variável para controle do número crescente
    DECLARE counter INT DEFAULT 0;

    -- Deleta as entradas na agenda_temp com o telefone especificado
    DELETE FROM agenda_temp WHERE telefone = telefone_filter;

    -- Inserir dados na agenda_temp com id_temp crescente
    INSERT INTO agenda_temp (id_temp, id_especialidade, id_agenda, horario_formatado, telefone)
        SELECT (@counter := @counter + 1) AS id_temp,
               id_especialidade, 
               id_agenda, 
               horario_formatado ,
               telefone_filter
        FROM agenda, (SELECT @counter := 0) AS init_counter
        WHERE id_especialidade = id_especialidade_filter 
          AND id_paciente IS NULL;

    -- Retorna os dados filtrados da tabela agenda_temp
    SELECT * 
    FROM agenda_temp at2
    WHERE telefone = telefone_filter 
      AND id_especialidade = id_especialidade_filter;
END;