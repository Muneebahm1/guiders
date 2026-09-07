<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="margin:0;padding:0;background:#F5F5F0;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F5F5F0;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;overflow:hidden;">
                    <tr>
                        <td style="background:#1F4E86;padding:18px 24px;">
                            <span style="color:#ffffff;font-size:16px;font-weight:bold;">THE GUIDERS</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;color:#1B1F22;font-size:14px;line-height:1.6;">
                            @if ($forLead)
                                <p>Hi {{ $lead->name }},</p>
                                <p>We've received your document. Here's a quick confirmation:</p>
                            @else
                                <p>A document was uploaded for <strong>{{ $lead->name }}</strong> by {{ $document->counselor->name }}.</p>
                            @endif

                            <table role="presentation" width="100%" cellpadding="4" cellspacing="0" style="font-size:13px;margin:16px 0;border-collapse:collapse;">
                                <tr><td style="color:#5B6570;width:140px;">Document type</td><td><strong>{{ $document->document_type }}</strong></td></tr>
                                <tr><td style="color:#5B6570;">File name</td><td><strong>{{ $document->file_name }}</strong></td></tr>
                                <tr><td style="color:#5B6570;">Uploaded on</td><td><strong>{{ $document->created_at->format('M j, Y g:i A') }}</strong></td></tr>
                                @if ($document->notes)
                                    <tr><td style="color:#5B6570;">Notes</td><td>{{ $document->notes }}</td></tr>
                                @endif
                            </table>

                            @if ($forLead)
                                <p>If this wasn't expected or you have questions, please contact your counselor.</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 24px 24px;color:#5B6570;font-size:12px;">
                            The Guiders Overseas Educational Services
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
