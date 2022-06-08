@extends('master')

@section('css')

@endsection


@section('content')

    <div class="container-fluid" id="miul" hidden>
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Banner" ><br>
        <h5 class="center-align">Compra Créditos</h5>
        <div class="row">
            <div class="input-field col s12" id="texto_creditos"></div>
            <div class="input-field col s12" id="cantidad_dinero"></div>
            <div class="input-field col s12" id="cantidad_creditos"></div>
            <div class="input field col s12 right-align" id="button_buy"></div>


        </div>
    </div>
    <div id="modal1" class="modal bottom-sheet">
        <div class="modal-content">
            <h5>Cual es tu WhatsApp ?</h5>
            <small>para ingresar al sistema, te enviaremos un pin a tu WhatsApp</small>
            <div class="row">
                <div class="col s9">
                    <input placeholder="Ingresa tu telefono - 8 digitos" id="telefono" type="number" class="validate">
                </div>
                <div class="col s3">
                    <a id="btn_telefono" style="background-color: #0C2746;" onclick="get_chofer()" class="waves-effect waves-light btn"><i class="material-icons">search</i></a>
                </div>
            </div>
            <div class="row">
                <div class="col s9">
                    <input placeholder="PIN - 4 digitos" id="pin" type="number" class="validate" disabled>
                </div>
                <div class="col s3">
                    <a id="btn_pin" style="background-color: #0C2746;"  onclick="get_pin()" class="waves-effect waves-light btn" disabled><i class="material-icons">key</i></a>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('javascript')

    <script>
        $('document').ready(function () {
            $('.tooltipped').tooltip();
            $('.modal').modal();
            $('select').formSelect();

            RecargarChofer();
        });

        async function RecargarChofer() {

            //Consulto si existe chofer
            var michofer = JSON.parse(localStorage.getItem('michofer'))
            if (michofer) {

                //Ingreso los datos de sesión consultando a la BD
                var michofer2 = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
                localStorage.setItem('michofer', JSON.stringify(michofer2.data))

                // $("#micategoria").val(michofer.categoria.name)
                M.toast({html: 'Bienvenido! '+michofer.nombres+' '+michofer.apellidos})
                $("#miul").attr('hidden', false);
                CargarDivs()
            } else {
                $('#modal1').modal('open');
            }

        }


        async function GeneralLinkBanipay(concepto,cantidad, precio) {
            var micart2 = []

            micart2.push({"concept": concepto, "quantity": cantidad, "unitPrice": precio})

            var miconfig = {"affiliateCode": "{{ setting('banipay.affiliatecode') }}",
                "notificationUrl": "{{ setting('banipay.notificacion') }}",//vistaurl
                "withInvoice": false,
                "externalCode": null,//credito_id
                "paymentDescription": "Pago por la compra en {{ setting('admin.title') }}",
                "details": micart2,
                "postalCode": "{{ setting('banipay.moneda') }}"
                }
            var banipay = await axios.post('https://banipay.me:8443/api/payments/transaction', miconfig)

            return banipay.data;

        }

        async function get_chofer() {
            var telefono = $("#telefono").val()
            if (telefono == '') {
                    M.toast({html : 'Ingresa un telefono valido'})
            } else {
                var michofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+telefono)
                if (michofer.data) {
                var pin = Math.floor(1000 + Math.random() * 9000);
                var chofer = await axios("{{ setting('admin.url_api') }}chofer/pin/save/"+michofer.data.id+"/"+pin)
                //send chatbot
                var mensaje="Hola, tu pin para acceder a APPXI es: "+pin
                var wpp= await axios("https://chatbot.appxi.net/?type=text&phone="+telefono+"&message="+mensaje)
                M.toast({html : 'Revisa tu Whatsapp'})
                $("#telefono").attr('disabled', true);
                $("#btn_telefono").attr('disabled', true);
                $("#pin").attr('disabled', false);
                $("#btn_pin").attr('disabled', false);
                } else {
                    location.href= '/chofer/crear'
                }
            }
        }

        async function get_pin() {
            var pin = $("#pin").val()
            var telefono = $("#telefono").val()
            var midata={'telefono':telefono, 'pin':pin}
            var michofer = await axios.post("{{ setting('admin.url_api') }}chofer/pin/get", midata)
            if (michofer.data) {
                localStorage.setItem('michofer', JSON.stringify(michofer.data))
                $('#modal1').modal('close')
                M.toast({html : 'Bienvenido'})
                $("#miul").attr('hidden', false);
                CargarDivs()
            }else{
                M.toast({html : 'Credenciales Invalidas'})
            }
        }

        async function CargarDivs() {

            var michofer = JSON.parse(localStorage.getItem('michofer'))

            if(michofer.categoria_id==1){
                var precio=parseFloat("{{setting('creditos.precio_credito_moto')}}")
            }
            if((michofer.categoria_id==2) || (michofer.categoria_id==3)){
                var precio=parseFloat("{{setting('creditos.precio_credito_auto')}}")
            }



            $('#texto_creditos').html("<p>Los Créditos son necesarios para realizar viajes, el costo de los mismos depende de la categoría, en su caso tienen un costo de: "+precio+" Bs por Crédito</p><p>El pago deberá ser realizado a través de una plataforma Online al momento de presionar el botón Comprar, si desea pagarlos de manera presencial contáctese con Soporte.</p>")
            $('#cantidad_dinero').html("<label for='cantidad_dinero_input'>Cantidad de Dinero</label><input type='text' class='validate' id='cantidad_dinero_input' name='cantidad_dinero_input' placeholder='Ingrese Cantidad Dinero' required>")
            $('#cantidad_creditos').html("<label for='cantidad_creditos_input'>Cantidad de Créditos</label><input type='text' class='validate' id='cantidad_creditos_input' name='cantidad_creditos_input' placeholder='Ingrese Cantidad Créditos' required>")
            $('#button_buy').html("<a class='waves-effect waves-light btn' id='button_buy_input'>Comprar</a>")


        }


    </script>

@endsection
