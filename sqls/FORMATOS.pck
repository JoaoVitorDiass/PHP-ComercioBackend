CREATE OR REPLACE NONEDITIONABLE PACKAGE FORMATOS IS

  -- AUTHOR  : JOAO VITOR
  -- CREATED : 04/08/2023 15:05:03
  -- PURPOSE : IMPLEMENTAR FUNCOES GENERICAS.


  -- PUBLIC FUNCTION AND PROCEDURE DECLARATIONS
  FUNCTION GET_CPFCNPJ(P_CPF_CNPJ IN NUMBER,
                       P_TIPO_PESSOA IN VARCHAR2 DEFAULT NULL)
  RETURN VARCHAR2;
  
  FUNCTION GET_RG(P_RG IN NUMBER)
  RETURN VARCHAR2;
  
  FUNCTION GET_CEP(P_CEP IN NUMBER)
  RETURN VARCHAR2;

  FUNCTION GET_TELEFONE(P_NUMERO IN NUMBER)
  RETURN VARCHAR2;

  FUNCTION GET_MAC_ADDRESS(P_MAC IN VARCHAR2, P_DIVISOR IN VARCHAR2 DEFAULT ':')
  RETURN VARCHAR2;
  
  FUNCTION CLEAR_FORMAT(P_STR IN VARCHAR2)
  RETURN NUMBER;
  
END FORMATOS;
/
CREATE OR REPLACE NONEDITIONABLE PACKAGE BODY FORMATOS IS

  FUNCTION GET_CPFCNPJ(P_CPF_CNPJ    IN NUMBER,
                       P_TIPO_PESSOA IN VARCHAR2 DEFAULT NULL)
    RETURN VARCHAR2 IS
    V_CPFCNPJ VARCHAR2(100);
  
  BEGIN
  
    IF (P_TIPO_PESSOA IS NOT NULL) THEN
      -- IDENTIFICA SE O QUE FOI PASSADO E UM CPF OU CNPJ
      IF P_TIPO_PESSOA = 'F' AND 99999999999 - P_CPF_CNPJ > 0 THEN
        -- CPF
      
        V_CPFCNPJ := SUBSTR(P_CPF_CNPJ, 1, 3) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 4, 3) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 7, 3) || '-' ||
                     SUBSTR(P_CPF_CNPJ, 10, 2);
      ELSE
        --CNPJ
      
        V_CPFCNPJ := SUBSTR(P_CPF_CNPJ, 1, 2) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 3, 3) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 6, 3) || '/' ||
                     SUBSTR(P_CPF_CNPJ, 9, 4) || '-' ||
                     SUBSTR(P_CPF_CNPJ, 13, 2);
      
      END IF;
    ELSE
      -- IDENTIFICA SE O QUE FOI PASSADO E UM CPF OU CNPJ
      IF 99999999999 - P_CPF_CNPJ > 0 THEN
        -- CPF
      
        V_CPFCNPJ := SUBSTR(P_CPF_CNPJ, 1, 3) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 4, 3) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 7, 3) || '-' ||
                     SUBSTR(P_CPF_CNPJ, 10, 2);
      ELSE
        --CNPJ
      
        V_CPFCNPJ := SUBSTR(P_CPF_CNPJ, 1, 2) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 3, 3) || '.' ||
                     SUBSTR(P_CPF_CNPJ, 6, 3) || '/' ||
                     SUBSTR(P_CPF_CNPJ, 9, 4) || '-' ||
                     SUBSTR(P_CPF_CNPJ, 13, 2);
      
      END IF;
    END IF;
  
    RETURN V_CPFCNPJ;
  
  END;

  FUNCTION GET_RG(P_RG IN NUMBER) RETURN VARCHAR2 IS
    V_RG VARCHAR2(100);
  
  BEGIN
    V_RG := REGEXP_REPLACE(P_RG, '[^0-9]', '');
  
    -- Formata o RG adicionando pontos e traço
    V_RG := SUBSTR(V_RG, 1, 2) || '.' ||
            SUBSTR(V_RG, 3, 3) || '.' ||
            SUBSTR(V_RG, 6, 3) || '-' ||
            SUBSTR(V_RG, 9);
  
    RETURN V_RG;
  END;

  FUNCTION GET_CEP(P_CEP IN NUMBER) RETURN VARCHAR2 IS
    V_CEP VARCHAR2(100);
  
  BEGIN
    V_CEP := LPAD(TRUNC(P_CEP), 8, '0');
    V_CEP := SUBSTR(V_CEP, 1, 5) || '-' || SUBSTR(P_CEP, 6);
  
    RETURN V_CEP;
  END;

  FUNCTION GET_TELEFONE(P_NUMERO IN NUMBER) RETURN VARCHAR2 IS
    V_TELEFONE VARCHAR2(100);
  
  BEGIN
  
    IF LENGTH(P_NUMERO) = 11 THEN
    
      V_TELEFONE := '(' || SUBSTR(TO_CHAR(P_NUMERO), 1, 2) || ') ' ||
                    SUBSTR(P_NUMERO, 3, 5) || '-' || SUBSTR(P_NUMERO, 8);
    
    ELSIF LENGTH(P_NUMERO) = 10 THEN
    
      V_TELEFONE := '(' || SUBSTR(TO_CHAR(P_NUMERO), 1, 2) || ') ' ||
                    SUBSTR(P_NUMERO, 3, 4) || '-' || SUBSTR(P_NUMERO, 7);
    
    ELSIF LENGTH(P_NUMERO) = 9 THEN
    
      V_TELEFONE := SUBSTR(P_NUMERO, 1, 5) || '-' || SUBSTR(P_NUMERO, 6);
    ELSE
    
      V_TELEFONE := SUBSTR(P_NUMERO, 1, 4) || '-' || SUBSTR(P_NUMERO, 5);
    
    END IF;
    RETURN V_TELEFONE;
  END;

  FUNCTION GET_MAC_ADDRESS(P_MAC     IN VARCHAR2,
                           P_DIVISOR IN VARCHAR2 DEFAULT ':') RETURN VARCHAR2 IS
    V_MAC VARCHAR2(200);
    V_I   NUMBER := 1;
  BEGIN
    WHILE (V_I <= LENGTH(P_MAC)) LOOP
      V_MAC := V_MAC || P_DIVISOR || SUBSTR(P_MAC, V_I, 2);
      V_I   := V_I + 2;
    END LOOP;
    --         DBMS_OUTPUT.PUT_LINE(LENGTH(P_DIVISOR));
  
    RETURN SUBSTR(V_MAC, 1 + LENGTH(P_DIVISOR));
  END;
  
  FUNCTION CLEAR_FORMAT(P_STR IN VARCHAR2) RETURN NUMBER IS
    N_CONTEUDO NUMBER;
    V_STR VARCHAR2(100);
  BEGIN
    V_STR := REPLACE(P_STR,'(','');
    V_STR := REPLACE(V_STR,')','');
    V_STR := REPLACE(V_STR,'.','');
    V_STR := REPLACE(V_STR,'-','');
    V_STR := REPLACE(V_STR,' ','');
    V_STR := REPLACE(V_STR,'_','');
    
    N_CONTEUDO := TO_NUMBER(V_STR);
    
    RETURN N_CONTEUDO;
  END;
  
  
  
END FORMATOS;
/
