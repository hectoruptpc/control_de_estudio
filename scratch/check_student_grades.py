import subprocess

res = subprocess.run(['C:\\xampp\\php\\php.exe', 'scratch/check_db.php'], capture_output=True)
print(res.stdout.decode('utf-8', errors='ignore'))
