
import base64
import hashlib
import hmac
import json
import random
import requests
import string
import time

class CloudTrax(object):

    def __init__(self, key, secret):
        self._key = key
        self._secret = secret
        self.api_server = 'https://api.cloudtrax.com'

    def _build_headers(self, endpoint, data=None):
    
        if (data == None):
            body = '';
        else:
            body = data;
    
        # n log_2(52) bits of entropy, at 8 chars this is 45 bits
        nonce = ''.join([random.choice(string.ascii_letters) for _ in xrange(8)])
        now = int(time.time());

        authorization = "key=" + self._key + ",timestamp=" + str(now) + ",nonce=" + nonce;
        signature = hmac.new(key=self._secret, msg=authorization + endpoint + body, digestmod=hashlib.sha256).hexdigest();

        headers = {
            "Authorization": authorization,
            "Signature": signature,
            "Content-Type": "application/json",
            "OpenMesh-API-Version": "1"
        }

        return headers

    def get(self, endpoint):
        headers = self._build_headers(endpoint);
        responce = requests.get(self.api_server + endpoint, headers=headers)
        return responce.json()

    def post(self, endpoint, data):
        body = json.dumps(data);
        headers = self._build_headers(endpoint, body);
        responce = requests.post(self.api_server + endpoint, headers=headers, data=body)
        return responce.json()

    def delete(self, endpoint):
        headers = self._build_headers(endpoint);
        responce = requests.delete(self.api_server + endpoint, headers=headers)
        return responce.json()

if __name__ == "__main__":
    
    key     = '<key-provided-by-open-mesh>'
    secret  = '<secret-provided-by-open-mesh>'
    
    api = CloudTrax(key,secret)

    # -----------------
    # list all networks
    # -----------------
    
    print "\n-- Network List --"
    result = api.get("/network/list")
    if 'errors' in result:
        print json.dumps(result['errors'])
    else:
        for x in result["networks"]:
            print str(x['id']) + " " + str(x['name'])

    # ----------------------------
    # create a new minimal network
    # ----------------------------
      
    #data = {
    #    'name': 'test-network-#10',
    #    'password': 'some_password',
    #    'timezone': 'America/Los_Angeles',
    #    'country_code': 'US'
    #}
    
    #del_id = None
    
    #print "\n-- Creating Network --"
    #result = api.post("/network", data)
    #if 'errors' in result:
    #    print json.dumps(result['errors'])
    #else:
    #    print "Created Netrork id# " + str(result['id'])
    #    del_id = result['id']
    
    # -----------------
    # list all networks
    # -----------------
    
    #print "\n-- Network List --"
    #result = api.get("/network/list")
    #if 'errors' in result:
    #    print json.dumps(result['errors'])
    #else:
    #    for x in result["networks"]:
    #        print str(x['id']) + " " + str(x['name'])
        
    # -------------------------------
    # delete the network just created
    # -------------------------------
    
    #print "\n-- Deleting Network --"
    #if ('del_id' in locals()):
    #    result = api.delete("/network/" + str(del_id))
    #    if 'errors' in result:
    #        print json.dumps(result['errors'])
    #    else:
    #        if result['code'] == 1009:
    #            print "Deleted network id# " + str(del_id)
    #        else:
    #            print json.dumps(result)

    # -----------------
    # list all networks
    # -----------------
    
    #print "\n-- Network List --"
    #result = api.get("/network/list")
    #if 'errors' in result:
    #    print json.dumps(result['errors'])
    #else:
    #    for x in result["networks"]:
    #        print str(x['id']) + " " + str(x['name'])
