import urllib.request, urllib.parse, urllib, sys, os, re, subprocess

# Settings
languageFilesFolder = 'C:\\Projekty\\Subnautica\\unityproj\\SNUnmanagedData\\LanguageFiles'
apiEndpoint = 'http://translate.unknownworlds.com/api/strings/translation-file?api_key=8f09d1dc4a2fc7708e1a91ea8f7a5551'
extractCommand = 'cd '+languageFilesFolder+' && "c:\\Program Files\\7-Zip\\7z.exe" e -y output.zip'

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
