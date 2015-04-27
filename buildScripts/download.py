import urllib.request, urllib.parse, urllib, sys, os, re, subprocess

# Settings
languageFilesFolder = 'C:\\Projekty\\Subnautica\\unityproj\\SNUnmanagedData\\LanguageFiles'
apiEndpoint = 'http://uwetranslate.dev/api/strings/translation-file?api_key=555'
extractCommand = '"c:\\Program Files\\7-Zip\\7z.exe" e -y '+languageFilesFolder+'\\output.zip'

# Download the file
http_request = urllib.request.urlopen (apiEndpoint).read()
zipFile = open(languageFilesFolder+'\\output.zip', 'wb+')
zipFile.write(http_request)
zipFile.close()

# Extract the file
subprocess.call(extractCommand, shell=True)

# Delete the file
os.remove(languageFilesFolder+'\\output.zip')

# Plactic - add files
subprocess.call('cd '+languageFilesFolder+' && cm rm ./ --nodisk && cm add -R ./', shell=True)

# Plastic - commit
subprocess.call('cd '+languageFilesFolder+' && cm checkin . -c="Translation files update"', shell=True)
