@switch($id_prod)
    @case(1)
        <img src=" {{ asset('img/empresas/givaudan.png') }}" width="200" alt="Givaudan" >
        @break
    @case(2)
        <img src=" {{ asset('img/empresas/memphis.png') }}" width="100" alt="Memphis" >
        @break
    @case(3)
        <img src=" {{ asset('img/empresas/chanel.png') }}" width="200" alt="Chanel" >
        @break
@endswitch
<br>