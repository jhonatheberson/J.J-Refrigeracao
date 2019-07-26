echo "export SENDGRID_API_KEY='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'" > sendgrid.env
echo "sendgrid.env" >> .gitignore
source ./sendgrid.env





# using SendGrid's Python Library
# https://github.com/sendgrid/sendgrid-python
import sendgrid
import os
from sendgrid.helpers.mail import *

sg = sendgrid.SendGridAPIClient(apikey=os.environ.get('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'))
from_email = Email("jhonatheberson@outlook.com.br")
to_email = Email("jhonatheberson@outlook.com.br")
subject = "Sending with SendGrid is Fun"
content = Content("text/plain", "and easy to do anywhere, even with Python")
mail = Mail(from_email, subject, to_email, content)
response = sg.client.mail.send.post(request_body=mail.get())
print(response.status_code)
print(response.body)
print(response.headers)