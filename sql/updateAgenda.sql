CREATE PROCEDURE updateAgenda(
    IN id_agenda_filter INT,
    IN id_paciente_filter INT,
    OUT affected_rows INT
)
BEGIN
    -- Executa o update
    UPDATE agenda 
    SET id_paciente = id_paciente_filter
    WHERE id_agenda = id_agenda_filter
      AND id_paciente IS NULL;
    
    -- Define a variável de saída com o número de linhas afetadas
    SET affected_rows = ROW_COUNT();
END;