@extends('main')

@section('content')
    <template v-if="menu==0">
       <h1>El Menú Principal 0 </h1>        
    </template>

    <template v-if="menu==1">
       <category-component></category-component>                  
    </template>

    <template v-if="menu==2">
        <product-component></product-component>       
    </template>

    <template v-if="menu==3">
        <h2>Menu 3</h2>         
    </template>

    <template v-if="menu==4">
         <provider-component></provider-component>                 
    </template>

    <template v-if="menu==5">
        <h2>Menu 5</h2>                     
    </template>

    <template v-if="menu==6">
         <client-component></client-component>                 
    </template>

    <template v-if="menu==7">
        <h2>Menu 7</h2>                       
    </template>

    <template v-if="menu==8">
        <h2>Menu 8</h2>                  
    </template>

    <template v-if="menu==9">
        <h2>Menu 9</h2>                  
    </template>

    <template v-if="menu==10">
        <h2>Menu 10</h2>                   
    </template>

    <template v-if="menu==11">
        <h2>Menu 11</h2>                  
    </template>

    <template v-if="menu==12">
        <h2>Menu 12</h2>                  
    </template>
@endsection