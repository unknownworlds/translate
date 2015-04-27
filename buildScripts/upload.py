import urllib.request, urllib.parse, sys, os, re

# Settings
languageFilesFolder = 'C:\\Projekty\\Subnautica\\unityproj\\SNUnmanagedData\\LanguageFiles'
apiEndpoint = 'http://uwetranslate.dev/api/strings/translation-file'

# Read and process the file (remove the comments)
f = open(languageFilesFolder+'/English.json', 'r')
json = ''

for line in f:
	if "//" not in line:
		json = json + line

# Hit the API with new data and print the output		
params = urllib.parse.urlencode({'project_id': 1, 'api_key': 555, 'data': json}).encode('utf-8')
http_request = urllib.request.urlopen(apiEndpoint, params).read()

print(http_request)
