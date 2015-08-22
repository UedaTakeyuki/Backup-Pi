# 判定法:http://d.hatena.ne.jp/flageo/20080219/p1
ifile=$1

case "$ifile" in
*\.gz)
  echo "Found .gz suffix"
  ;;
*)
  echo "Not a .gz suffix"
  ;;
esac

