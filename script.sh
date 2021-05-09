#!/bin/bash

set -euxo pipefail

vagrant up
ssh-keygen -R 192.168.50.50
ssh -o "StrictHostKeyChecking no" vagrant@192.168.50.50 'sleep 2 &'  # exits after 5 seconds
# vagrant ssh && 'sleep 5 >/dev/null &'
ansible-playbook -i hosts playbook.yml
