<!DOCTYPE html>
<html>
<head>
    <title>Guest Session Details</title>
</head>
<body>
    <p>Hello,</p>
    <p>You are invited to a guest session titled "{{ $guestLecture->guessionsession_title }}".</p>
    <p><strong>Speaker:</strong> {{ $guestLecture->speaker->speakername }}</p>
    <p><strong>Date and Time:</strong> {{ $guestLecture->guestsession_date_time }}</p>
    <p><strong>Duration:</strong> {{ $guestLecture->guest_session_duration }} minutes</p>
    <p><strong>Platform:</strong> {{ $guestLecture->platform }}</p>
    <p>We look forward to your participation.</p>
</body>
</html>
