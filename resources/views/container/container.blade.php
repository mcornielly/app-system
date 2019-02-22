@extends('main')

@section('content')

    {{-- Contenido del Menú --}}
     @if(Auth::check())
        @if(Auth::user()->role_id == 1)
            <template v-if="menu==0">
               <h1>Escritorio</h1>        
            </template>

            <template v-if="menu==1">
               <category-component></category-component>                  
            </template>

            <template v-if="menu==2">
                <product-component></product-component>       
            </template>

            <template v-if="menu==3">
                <income-component><income-component>         
            </template>

            <template v-if="menu==4">
                 <provider-component></provider-component>                 
            </template>

            <template v-if="menu==5">
                <sale-component></sale-component>                     
            </template>

            <template v-if="menu==6">
                 <client-component></client-component>                 
            </template>

            <template v-if="menu==7">
                <user-component></user-component>                  
            </template>

            <template v-if="menu==8">
                 <role-component></role-component>                 
            </template>

            <template v-if="menu==9">
                <h2>Reporte de Ingresos</h2>                  
            </template>

            <template v-if="menu==10">
                <h2>Reporte de Ventas</h2>                   
            </template>

            <template v-if="menu==11">
                <h2>Ayuda</h2>                  
            </template>

            <template v-if="menu==12">
                <h2>Acerca de</h2>                  
            </template>
        @elseif (Auth::user()->role_id == 2)
            <template v-if="menu==5">
                <sale-component></sale-component>                     
            </template>

            <template v-if="menu==6">
                 <client-component></client-component>                 
            </template>
                <template v-if="menu==10">
                <h2>Reporte de Ventas</h2>                   
            </template>

            <template v-if="menu==11">
                <h2>Ayuda</h2>                  
            </template>

            <template v-if="menu==12">
                <h2>Acerca de</h2>                  
            </template>   
        @elseif (Auth::user()->role_id == 3)
            <template v-if="menu==1">
               <category-component></category-component>                  
            </template>

            <template v-if="menu==2">
                <product-component></product-component>       
            </template>

            <template v-if="menu==3">
                <income-component><income-component>        
            </template>

            <template v-if="menu==4">
                 <provider-component></provider-component>                 
            </template>

            <template v-if="menu==9">
                <h2>Reporte de Ingresos</h2>                  
            </template>

            <template v-if="menu==11">
                <h2>Ayuda</h2>                  
            </template>

            <template v-if="menu==12">
                <h2>Acerca de</h2>                  
            </template>
        @else

        @endif    
     @endif 

@endsection