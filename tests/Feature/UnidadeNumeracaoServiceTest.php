<?php

namespace Tests\Unit;

use App\Service\UnidadeNumeracaoService;
use Tests\TestCase;

class UnidadeNumeracaoServiceTest extends TestCase
{
   public function test_sem_prefixo_comeca_com_zero()
{
    $service = new UnidadeNumeracaoService();

    $this->assertEquals(
        ['01','02','03','04','11','12','13','14'],
        $service->gerarNumeracao('01-04', 2)
    );
}

public function test_prefixo_1()
{
    $service = new UnidadeNumeracaoService();


    $this->assertEquals(
        ['101','102','103','104','111','112','113','114'],
        $service->gerarNumeracao('1/01-04', 2)
    );
}

public function test_prefixo_10()
{
    $service = new UnidadeNumeracaoService();

    $this->assertEquals(
        ['1001','1002','1003','1004','1011','1012','1013','1014'],
        $service->gerarNumeracao('10/01-04', 2)
    );


}

public function test_nao_duplica_quando_maior_que_10()
{
    $service = new UnidadeNumeracaoService();

    $resultado = $service->gerarNumeracao('01-14', 4);

    // garante que não tem duplicado
    $this->assertCount(count(array_unique($resultado)), $resultado);
}

public function test_respeita_fim_da_faixa()
{
    $service = new UnidadeNumeracaoService();

    $resultado = $service->gerarNumeracao('01-14', 1);

    $this->assertEquals('14', end($resultado));
}

public function test_padrao_condominio_real()
{
    $service = new UnidadeNumeracaoService();

    $this->assertEquals(
        [
            // 1º pavimento
            '101','102','103','104','105','106','107','108','109','110','111','112','113','114',

            // 2º pavimento
            '201','202','203','204','205','206','207','208','209','210','211','212','213','214',

            // 3º pavimento
            '301','302','303','304','305','306','307','308','309','310','311','312','313','314',
        ],
        $service->gerarNumeracaoCondominio('01-14', 3)
    );
}



}
