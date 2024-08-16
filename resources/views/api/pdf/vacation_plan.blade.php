@extends('api.layouts.basic')

@section('body')

    <div class="content-page">
        <div class="title">
            <h1>2024 - Vacation Plan</h1>
            <h2>2024 Dev Team Test</h2>
        </div>

        <div class="info-page">
            <div class="plan">
                <h3>Holiday Plan - </h3>
                    <p> {{ $plan->title }} </p>
                <h3>Description - </h3> 
                    <p> {{ $plan->description }} </p>
                <h3>Date - </h3> 
                    <p>{{ $plan->date }}</p>
                <h3>Location - </h3> 
                    <p>{{ $plan->location }}</p>

                @if($plan->participants)
                    <h3>Participants - </h3> 
                        <p>{{ $plan->participants }}</p>
                @endif

            </div>
        </div>

    </div>

@endsection