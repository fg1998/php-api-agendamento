UPDATE agenda
SET horario_formatado = CONCAT(
    CASE DAYOFWEEK(horario)
        WHEN 1 THEN 'Domingo'
        WHEN 2 THEN 'Segunda'
        WHEN 3 THEN 'Terça'
        WHEN 4 THEN 'Quarta'
        WHEN 5 THEN 'Quinta'
        WHEN 6 THEN 'Sexta'
        WHEN 7 THEN 'Sábado'
    END,
    ' ',
    DATE_FORMAT(horario, '%d/%m %H:%i')
);
