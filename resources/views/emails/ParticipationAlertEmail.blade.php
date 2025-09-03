<!DOCTYPE html>
<html>
<head>
    <title>Nuovo Partecipante al Tuo Evento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <tr>
            <td style="padding: 40px 30px; text-align: center;">
                <h1 style="color: #333333; font-size: 24px; margin: 0 0 20px;">Ottime Notizie!</h1>
                <p style="color: #666666; font-size: 16px; line-height: 1.5; margin: 0 0 20px;">
                    Un nuovo membro, <strong>{{ $user->username }}</strong>, ha partecipato al tuo evento, <strong>{{ $event->title }}</strong>, il {{ $event->start_date }} presso {{ $event->address }}.
                </p>
                <p style="color: #666666; font-size: 16px; line-height: 1.5; margin: 0 0 20px;">
                    Questo è un traguardo fantastico per il tuo evento! Grazie per aver creato un’esperienza così coinvolgente da attrarre nuovi membri nella community di Evoka.
                </p>
                <p style="color: #666666; font-size: 16px; line-height: 1.5; margin: 0 0 30px;">
                    Continua con questo slancio organizzando altri eventi per far crescere il tuo pubblico e ispirare la nostra community.
                </p>
                <a href="{{ url('/') }}" style="display: inline-block; padding: 12px 24px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 16px;">Organizza il Tuo Prossimo Evento</a>
                <p style="color: #666666; font-size: 14px; line-height: 1.5; margin: 30px 0 0;">
                    Cordiali saluti,<br>
                    <strong>Il Team di Evoka</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 30px; text-align: center; background-color: #f8f8f8; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                <p style="color: #999999; font-size: 12px; margin: 0;">
                    Hai bisogno di supporto o vuoi inviarci un feedback? Contattaci a <a href="mailto:info@evoka.info" style="color: #007bff; text-decoration: none;">info@evoka.info</a>
                </p>
                <p style="color: #999999; font-size: 12px; margin: 10px 0 0;">
                    © 2025 Evoka. Tutti i diritti riservati.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>