#!/usr/bin/python3

import requests
import json
import configapi as cfg

# Config

# api_url = 'https://botsin.space/api/v1'
# account_id = '106691813787549443'
# access_token = 'xxxxxxxxxxxxxxxx'

api_url = cfg.api_url
account_id = cfg.account_id
access_token = cfg.access_token

# Authorization header

headers = {
	'Authorization': 'Bearer ' + access_token
}

# Get statuses count

response = requests.get(api_url + '/accounts/' + account_id)
data = response.json()

statuses_count = data['statuses_count']

# Read quotes file

with open('civquotes.json', 'r') as quotes_file:
	data = quotes_file.read()

# Parse quotes file

quotes = json.loads(data)

# Get next quote

quote_key = list(quotes.keys())[statuses_count]
quote_values = list(quotes.values())[statuses_count]

tag = '#' + quote_key
file = quote_values['audio']
description = quote_values['description']

description_split = description.split(' - ')
cite = description_split.pop(-1)
quote = (' - ').join(description_split)

status = tag + '\n\n' + quote + '\n\n' + '- ' + cite

# Upload media

files = {
	'file': (file, open('assets/' + file, 'rb'))
}

response = requests.post(api_url + '/media', headers=headers, files=files)
data = response.json()

media_ids = [data['id']]

# Post status

headers['Content-Type'] = 'application/json'

data = {
	'status': status,
	'media_ids': media_ids
}

requests.post(api_url + '/statuses', headers=headers, data=json.dumps(data))
