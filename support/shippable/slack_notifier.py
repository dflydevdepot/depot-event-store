import httplib, json
import getopt, sys, os
import subprocess
import xml.etree.ElementTree as ET

def get_coverage_clover(coverage_clover):
  tree = ET.parse(coverage_clover)
  root = tree.getroot()

  metrics = root.findall('./project/metrics')[0]

  coveredstatements = float(metrics.attrib['coveredstatements'])
  statements = float(metrics.attrib['statements'])

  return "{0:.2f}%".format((coveredstatements/statements) * 100)

def get_connection():
  return httplib.HTTPSConnection('hooks.slack.com')

def get_url(token):
  return '/services/%s' % token

def get_data_from_git(format_string, commit):
  return subprocess.check_output(['git', 'log', '-1', '--format=format:%s' % format_string, commit])

def get_author(commit):
  return get_data_from_git('%an', commit)

def get_date(commit):
  return get_data_from_git('%aD', commit)

def get_title(commit):
  return get_data_from_git('%s', commit)

def get_full_message(commit):
  return get_data_from_git('%b', commit)

def get_short_commit_hash(commit):
  return get_data_from_git('%h', commit)

def post_message(connection, url, success, project, coverage=None):
  headers = {'Content-Type': 'application/json'}
  build_url = os.environ['BUILD_URL']
  build_number = os.environ['BUILD_NUMBER']
  branch = os.environ['BRANCH']
  commit = os.environ['COMMIT']
  compare_url = os.environ['COMPARE_URL']

  status_text = 'succeeded' if success else 'failed'
  color = 'good' if success else 'danger'
  if coverage is None:
    coverage = 'no'
  text = '<%s|Build #%s> %s for project %s on branch %s (%s code coverage)' % (build_url, build_number, status_text, project, branch, coverage)

  message = {
    'username': 'Shippable',
    'fallback': text,
    'pretext': text,
    'color': color,
    'fields': [
      {
        'value': '<%s|%s>: %s - %s' % (compare_url, get_short_commit_hash(commit), get_title(commit), get_author(commit))
      }
    ]
  }

  connection.request('POST', url, json.dumps(message), headers)
  response = connection.getresponse()
  print response.read().decode()

def main():
  try:
    opts, args = getopt.getopt(sys.argv[1:], ':sf', ['project=', 'token=', 'coverage-clover='])
  except getopt.GetoptError as err:
    print str(err)
    sys.exit(2)

  success = False
  project = None
  token = None
  coverage = None
  for o, arg in opts:
    if o == '-s':
      success = True
    elif o == '--project':
      project = arg
    elif o == '--token':
      token = arg
    elif o == '--coverage-clover':
      coverage = get_coverage_clover(arg)

  connection = get_connection()
  url = get_url(token)
  post_message(connection, url, success, project, coverage)

if __name__ == '__main__':
  main()
